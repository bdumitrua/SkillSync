const express = require("express");
const router = express.Router();

const authToken = require("../middleware/authToken");

const {
	getChatMessages,
	getUserChats,
	getChatMembers,
	createNewChat,
} = require("../controllers/chatsController");

router.get("/", authToken, getUserChats);
router.get("/:id", authToken, getChatMessages);
router.get("/members/:id", authToken, getChatMembers);
router.post("/", authToken, createNewChat);

module.exports = router;
