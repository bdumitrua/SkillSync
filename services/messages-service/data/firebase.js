// const firebase = require("firebase");
const { initializeApp } = require("firebase/app");
const { getDatabase, ref, set } = require("firebase/database");
const fs = require("fs");

const firebaseConfig = JSON.parse(fs.readFileSync("./firebase.json"));

const app = initializeApp(firebaseConfig);
const database = getDatabase();

function sendMessage(chatId, userId, messageData) {
	set(ref(database, `messages/${chatId}/${userId}`), messageData);
}

function getChatMessages(chatId, userId) {}

function createChat(chatId, chatData) {
	set(ref(database, `chats/${chatId}`), chatData);
}

function getChatData(chatId) {}

function addChatMember(chatId, userId, memberData) {}

function getChatMembers(chatId) {}

function getLastChatId() {}

function addUserChat(userId, chatId) {}

function getUserChats(userId) {}

module.exports = {
	sendMessage,
	getChatMessages,
	createChat,
	getChatData,
	addChatMember,
	getChatMembers,
	getLastChatId,
	addUserChat,
	getUserChats,
};
