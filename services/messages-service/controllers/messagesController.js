const { v4: uuidv4 } = require("uuid");

const sendMessageToChat = (req, res) => {
	const chatId = req.params.chatId;

	res.send(`Sending message to chatId: ${chatId}`);
};

const deleteMessage = (req, res) => {
	const messageUuid = req.params.messageUuid;

	res.send(`Deleting message with uuid: ${messageUuid}`);
};

const deleteMessageForever = (req, res) => {
	const messageUuid = req.params.messageUuid;

	res.send(`Deleting forever message with uuid: ${messageUuid}`);
};

module.exports = {
	sendMessageToChat,
	deleteMessage,
	deleteMessageForever,
};
