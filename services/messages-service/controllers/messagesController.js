const {
	fbSendMessage,
	fbDeleteMessage,
	fbReadMessage,
} = require("../data/firebase");
const HttpException = require("../exceptions/httpException");
const { checkIfUserIsMember } = require("./chatsController");

const sendMessageToChat = async (req, res, next) => {
	try {
		const userId = +req.userId;
		const chatId = +req.params.chatId;
		await checkIfUserIsMember(userId, chatId);
		const now = new Date().toISOString();

		if (!req.body.text) {
			throw new HttpException(422, "Text field is required");
		}

		const messageData = {
			senderId: userId,
			text: req.body.text,
			status: "unread",
			created_at: now,
		};

		await fbSendMessage(chatId, userId, messageData);

		res.status(201).json({
			message: "Message successfully sended to chat",
		});
	} catch (error) {
		next(error);
	}
};

const readMessage = async (req, res, next) => {
	try {
		const messageUuid = req.params.messageUuid;
		const userId = +req.userId;
		const chatId = +req.params.chatId;
		await checkIfUserIsMember(userId, chatId);

		await fbReadMessage(chatId, userId, messageUuid);

		res.status(200).send();
	} catch (error) {
		next(error);
	}
};

const deleteMessage = async (req, res, next) => {
	try {
		const messageUuid = req.params.messageUuid;
		const userId = +req.userId;
		const chatId = +req.params.chatId;
		await checkIfUserIsMember(userId, chatId);

		await fbDeleteMessage(chatId, userId, messageUuid);

		res.status(200).json({
			message: "Message successfully deleted",
		});
	} catch (error) {
		next(error);
	}
};

module.exports = {
	sendMessageToChat,
	readMessage,
	deleteMessage,
};
