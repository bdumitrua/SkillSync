const express = require("express");
const router = express.Router();

const { getLastChatId, createChat } = require("../data/firebase");
const authToken = require("../middleware/authToken");

router.get("/", authToken, (req, res) => {
	res.send(`User ${req.userId} ${req.userEmail} chats`);
});

router.get("/:id", authToken, (req, res) => {
	const chatId = req.params.id;

	res.send(`Chat id ${chatId} messages`);
});

router.get("/members/:id", authToken, (req, res) => {
	const chatId = req.params.id;

	res.send(`Chat id ${chatId} members`);
});

router.post("/", authToken, (req, res) => {
	const teamId = req.body.teamId;
	const name = req.body.name;
	const avatarUrl = req.body.avatarUrl;
	// const newChatId = +getLastChatId() + 1;
	const newChatId = 1;

	const chatData = {
		teamId,
		name,
		avatarUrl,
		chatId: newChatId,
	};

	const newChat = createChat(newChatId, chatData);

	res.send(`Created chat with data: ${JSON.stringify(newChat)}`);
});

module.exports = router;

// 	"chats": {
//     "chat_id_1": {
//       "team_id": "team_id_1",
//       "name": "Название чата",
//       "avatar_url": "URL аватара",
//       "created_at": "2024-05-01T12:00:00Z"
//     },
//     "chat_id_2": {
//       ...
//     },
//     ...
//   },
// 	"userChats": {
//     "user_id_1": {
//       "chat_ids": {
//         "chat_id_1": true,
//         "chat_id_2": true,
//         ...
//       }
//     },
//     "user_id_2": {
//       ...
//     },
//     ...
//   }
// 	"messages": {
//     "chat_id_1": {
//       "user_id_1": {
//         "message_id_1": {
//           "sender_id": "user_id_1",
//           "text": "Привет!",
//           "created_at": "2024-05-01T12:00:00Z",
//           "status": "unread"
//         },
//         "message_id_2": {
//           "sender_id": "user_id_2",
//           "text": "Привет, как дела?",
//           "created_at": "2024-05-01T12:01:00Z",
//           "status": "unread"
//         },
//         ...
//       },
//       "user_id_2": {
//         ...
//       },
//       ...
//     },
//     "chat_id_2": {
//       ...
//     },
//     ...
//   }
