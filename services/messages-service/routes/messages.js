const express = require("express");
const router = express.Router();

const authToken = require("../middleware/authToken");

const {
	sendMessageToChat,
	deleteMessage,
	deleteMessageForever,
} = require("../controllers/messagesController");

router.post("/send/:chatId", authToken, sendMessageToChat);
router.delete("/delete/:messageUuid", authToken, deleteMessage);
router.delete("/delete/forever/:messageUuid", authToken, deleteMessageForever);

module.exports = router;
