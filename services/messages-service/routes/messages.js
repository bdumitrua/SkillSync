const express = require("express");
const router = express.Router();

const {
	sendMessageToChat,
	deleteMessage,
	deleteMessageForever,
} = require("../controllers/messagesController");

router.get("/send/:chatId", sendMessageToChat);
router.delete("/delete/:messageUuid", deleteMessage);
router.delete("/delete/forever/:messageUuid", deleteMessageForever);

module.exports = router;
