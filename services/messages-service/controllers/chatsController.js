const {
	fbGetLastChatId,
	fbCreateChat,
	fbGetChatMembers,
	fbGetUserChats,
	fbGetMultipleChatsData,
	fbGetChatMessages,
	fbUserIsChatMember,
	fbAddChatMember,
	fbRemoveChatMember,
} = require("../data/firebase");
const HttpException = require("../exceptions/httpException");

const getChatMessages = async (req, res, next) => {
	try {
		const userId = +req.userId;
		const chatId = +req.params.id;
		await checkIfUserIsMember(userId, chatId);

		const chatMessages = await fbGetChatMessages(chatId);

		res.status(200).json(chatMessages);
	} catch (error) {
		next(error);
	}
};

const getUserChats = async (req, res, next) => {
	try {
		const userId = +req.userId;

		const userChatsObjects = await fbGetUserChats(userId);
		// Получаем ключи из объекта, что эквивалентно получению ID чатов
		const userChatsIds = Object.keys(userChatsObjects).map((id) => +id);

		if (userChatsIds.length < 1) {
			return res.status(200).json([]);
		}

		const userChatsData = await fbGetMultipleChatsData(userChatsIds);
		return res.status(200).json(userChatsData);
	} catch (error) {
		next(error);
	}
};

const getChatMembers = async (req, res, next) => {
	try {
		const chatId = +req.params.id;
		await checkIfUserIsMember(req.userId, chatId);

		const chatMembersData = await fbGetChatMembers(chatId);
		// Получаем ключи из объекта, что эквивалентно получению ID участников
		const chatMembersIds = Object.keys(chatMembersData).map((id) => +id);

		res.status(200).json(chatMembersIds);
	} catch (error) {
		next(error);
	}
};

const addChatMember = async (req, res, next) => {
	try {
		const chatId = +req.params.chatId;
		const userId = +req.params.userId;
		await checkIfUserIsMember(req.userId, chatId);

		if (await fbUserIsChatMember(userId, chatId)) {
			return res.status(400).json({
				error: `User ${userId} is already member of chat ${chatId}`,
			});
		}

		await fbAddChatMember(chatId, userId);

		res.status(201).send();
	} catch (error) {
		next(error);
	}
};

const removeChatMember = async (req, res, next) => {
	try {
		const chatId = +req.params.chatId;
		const userId = +req.params.userId;
		// TODO CHECK IF IS A MODERATOR
		await checkIfUserIsMember(req.userId, chatId);

		await fbRemoveChatMember(chatId, userId);

		res.status(200).send();
	} catch (error) {
		next(error);
	}
};

const createNewChat = async (req, res, next) => {
	try {
		// TODO check if is admin of team
		const adminId = req.userId;
		// TODO add team members array and add them to chat members
		const teamId = req.body.teamId;
		const name = req.body.name;
		const avatarUrl = req.body.avatarUrl;
		const chatId = (await fbGetLastChatId()) + 1;

		const chatData = {
			adminId,
			teamId,
			name,
			avatarUrl,
			chatId,
		};

		await fbCreateChat(chatId, chatData);
		res.status(201).json({ message: "New chat successfully created" });
	} catch (error) {
		next(error);
	}
};

const checkIfUserIsMember = async (userId, chatId) => {
	const isMember = await fbUserIsChatMember(userId, chatId);

	if (!isMember) {
		throw new HttpException(403, "You are not member of this chat");
	}
};

module.exports = {
	getChatMessages,
	getUserChats,
	getChatMembers,
	addChatMember,
	removeChatMember,
	createNewChat,
	checkIfUserIsMember,
};
