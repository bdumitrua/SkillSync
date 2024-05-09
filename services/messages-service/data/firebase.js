// const firebase = require("firebase");
const { initializeApp } = require("firebase/app");
const { getDatabase, ref, set } = require("firebase/database");
const fs = require("fs");

const firebaseConfig = JSON.parse(fs.readFileSync("./firebase.json"));

const app = initializeApp(firebaseConfig);
const database = getDatabase();

function fbSendMessage(chatId, userId, messageData) {
	set(ref(database, `messages/${chatId}/${userId}`), messageData);
}

function fbGetChatMessages(chatId, userId) {}

function fbCreateChat(chatId, chatData) {
	set(ref(database, `chats/${chatId}`), chatData);
}

function fbGetChatData(chatId) {}

function fbAddChatMember(chatId, userId, memberData) {}

function fbGetChatMembers(chatId) {}

function fbGetLastChatId() {}

function fbAddUserChat(userId, chatId) {}

function fbGetUserChats(userId) {}

module.exports = {
	fbSendMessage,
	fbGetChatMessages,
	fbCreateChat,
	fbGetChatData,
	fbAddChatMember,
	fbGetChatMembers,
	fbGetLastChatId,
	fbAddUserChat,
	fbGetUserChats,
};

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
