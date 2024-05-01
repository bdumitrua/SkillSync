var express = require("express");
const { getLastChatId, createChat } = require("../data/firebase");
var router = express.Router();

router.get("/", function (req, res, next) {
	res.send("User chats");
});

router.get("/:id", function (req, res, next) {
	const chatId = req.params.id;

	res.send(`Chat id ${chatId} messages`);
});

router.get("/members/:id", function (req, res, next) {
	const chatId = req.params.id;

	res.send(`Chat id ${chatId} members`);
});

router.post("/", function (req, res, next) {
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
