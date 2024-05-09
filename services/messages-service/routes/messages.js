const express = require("express");
const router = express.Router();

const authToken = require("../middleware/authToken");

const {
	sendMessageToChat,
	deleteMessage,
	readMessage,
} = require("../controllers/messagesController");

router.post("/send/:chatId", authToken, sendMessageToChat);
router.post("/read/:chatId/:messageUuid", authToken, readMessage);
router.delete("/delete/:chatId/:messageUuid", authToken, deleteMessage);

module.exports = router;
