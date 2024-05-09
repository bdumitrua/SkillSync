const express = require("express");
const router = express.Router();

const authToken = require("../middleware/authToken");

const {
	getChatMessages,
	getUserChats,
	getChatMembers,
	createNewChat,
	addChatMember,
	removeChatMember,
} = require("../controllers/chatsController");

router.get("/", authToken, getUserChats);
router.get("/:id", authToken, getChatMessages);
router.post("/", authToken, createNewChat);

router.get("/members/:id", authToken, getChatMembers);
router.post("/members/:chatId/:userId", authToken, addChatMember);
router.delete("/members/:chatId/:userId", authToken, removeChatMember);

module.exports = router;
