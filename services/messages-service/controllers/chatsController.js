const { fbGetLastChatId, fbCreateChat } = require("../data/firebase");

const getChatMessages = (req, res) => {
	const chatId = req.params.id;
	res.send(`Chat id ${chatId} messages`);
};

const getUserChats = (req, res) => {
	res.send(`User ${req.userId} ${req.userEmail} chats`);
};

const getChatMembers = (req, res) => {
	const chatId = req.params.id;

	res.send(`Chat id ${chatId} members`);
};

const createNewChat = (req, res) => {
	const teamId = req.body.teamId;
	const name = req.body.name;
	const avatarUrl = req.body.avatarUrl;
	// const newChatId = +fbGetLastChatId() + 1;
	const newChatId = 1;

	const chatData = {
		teamId,
		name,
		avatarUrl,
		chatId: newChatId,
	};

	const newChat = fbCreateChat(newChatId, chatData);

	res.send(`Created chat with data: ${JSON.stringify(newChat)}`);
};

module.exports = {
	getChatMessages,
	getUserChats,
	getChatMembers,
	createNewChat,
};
