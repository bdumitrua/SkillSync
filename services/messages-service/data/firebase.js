const { ref, set, get, update, remove } = require("firebase/database");
const db = require("../config/firebaseConfig");
const HttpException = require("../exceptions/httpException");
const { v4: uuidv4 } = require("uuid");

const fbCreateChat = async (chatId, chatData) => {
	const chatRef = ref(db, `chats/${chatId}`);

	// Проверяем, существует ли чат
	const snapshot = await get(chatRef);
	if (snapshot.exists()) {
		console.log(`Chat with ID ${chatId} already exists.`);
		throw new HttpException(400, `Chat with ID ${chatId} already exists.`);
	}

	await set(chatRef, chatData);
	await fbSetLastChatId(chatId);
	await fbAddChatMember(chatId, chatData?.adminId);

	console.log("Data saved successfully.");
};

const fbSetLastChatId = async (chatId) => {
	validateChatId(chatId);

	const lastIdRef = ref(db, "config/lastChatId");
	await set(lastIdRef, chatId);
	console.log("Last chat ID updated successfully.");
};

const fbGetLastChatId = async () => {
	const lastIdRef = ref(db, "config/lastChatId");

	const snapshot = await get(lastIdRef);
	if (snapshot.exists()) {
		return snapshot.val();
	} else {
		await set(lastIdRef, 0);
		return 0;
	}
};

const fbAddChatMember = async (chatId, userId) => {
	validateChatId(chatId);
	validateUserId(userId);

	const updates = {};
	updates[`/chatMembers/${chatId}/${userId}`] = true;
	updates[`/userChats/${userId}/${chatId}`] = true;

	await update(ref(db), updates);
	console.log(`Successfully added user ${userId} to chat ${chatId}`);
};

const fbRemoveChatMember = async (chatId, userId) => {
	validateChatId(chatId);
	validateUserId(userId);

	const chatMemberRef = ref(db, `chatMembers/${chatId}/${userId}`);
	const userChatRef = ref(db, `userChats/${userId}/${chatId}`);

	const chatMemberSnapshot = await get(chatMemberRef);
	if (chatMemberSnapshot.exists()) {
		await remove(chatMemberRef);
		console.log(`User ${userId} removed from chat ${chatId}.`);
	}

	const userChatSnapshot = await get(userChatRef);
	if (userChatSnapshot.exists()) {
		await remove(userChatRef);
		console.log(`Chat ${chatId} removed from user ${userId}'s chat list.`);
	}
};

const fbSendMessage = async (chatId, userId, messageData) => {
	validateChatId(chatId);
	validateUserId(userId);

	const newMessageUUID = uuidv4();
	const newMessageRef = ref(db, `messages/${chatId}/${newMessageUUID}`);

	await set(newMessageRef, messageData);
	console.log("Message data saved successfully.");
};

const fbReadMessage = async (chatId, userId, messageUuid) => {
	validateChatId(chatId);
	validateUserId(userId);

	const messageRef = ref(db, `messages/${chatId}/${messageUuid}`);
	const snapshot = await get(messageRef);

	if (!snapshot.exists()) {
		throw new HttpException(
			404,
			`Message with UUID ${messageUuid} not found in chat ${chatId}.`
		);
	}

	const messageData = snapshot.val();
	if (messageData.status === "unread") {
		await update(messageRef, { status: "readed" });
		console.log(`Message ${messageUuid} in chat ${chatId} marked as read.`);
	} else {
		console.log(
			`Message ${messageUuid} in chat ${chatId} already marked as read.`
		);
	}
};

const fbDeleteMessage = async (chatId, userId, messageUuid) => {
	validateChatId(chatId);
	validateUserId(userId);

	const messageRef = ref(db, `messages/${chatId}/${messageUuid}`);
	const snapshot = await get(messageRef);

	if (!snapshot.exists()) {
		throw new HttpException(
			404,
			`Message with UUID ${messageUuid} not found in chat ${chatId}.`
		);
	}

	const messageData = snapshot.val();
	if (messageData.senderId !== userId) {
		throw new HttpException(
			403,
			`You can't delete other memeber's messages.`
		);
	}

	await remove(messageRef);
	console.log(
		`Message ${messageUuid} from chat ${chatId} deleted successfully.`
	);
};

const fbGetChatMessages = async (chatId) => {
	validateChatId(chatId);

	const chatMessagesRef = ref(db, `messages/${chatId}`);
	const snapshot = await get(chatMessagesRef);
	if (snapshot.exists()) {
		return snapshot.val();
	} else {
		return [];
	}
};

const fbUserIsChatMember = async (userId, chatId) => {
	validateUserId(userId);
	validateChatId(chatId);

	const chatMemberRef = ref(db, `chatMembers/${chatId}/${userId}`);

	const snapshot = await get(chatMemberRef);

	return snapshot.val() !== null;
};

const fbGetChatMembers = async (chatId) => {
	validateChatId(chatId);

	const chatMembersRef = ref(db, `chatMembers/${chatId}`);

	const snapshot = await get(chatMembersRef);
	if (snapshot.exists()) {
		return snapshot.val();
	} else {
		throw new Error(`No members found for chat with ID ${chatId}`);
	}
};

const fbGetChatData = async (chatId) => {
	validateChatId(chatId);

	const chatRef = ref(db, `chats/${chatId}`);

	const snapshot = await get(chatRef);
	if (snapshot.exists()) {
		return snapshot.val();
	} else {
		return null;
	}
};

const fbGetMultipleChatsData = async (chatIds) => {
	validateChatIds(chatIds);

	// Создаём массив с промисами для получения данных о каждом чате
	const chatDataPromises = chatIds.map(async (chatId) => {
		const chatRef = ref(db, `chats/${chatId}`);
		const snapshot = await get(chatRef);
		return snapshot.exists() ? { chatId, ...snapshot.val() } : null;
	});

	// Выполняем все запросы параллельно и фильтруем результаты от `null`
	const chatDataArray = await Promise.all(chatDataPromises);
	return chatDataArray.filter(Boolean);
};

const fbGetUserChats = async (userId) => {
	validateUserId(userId);

	const userChatsRef = ref(db, `userChats/${userId}`);

	const snapshot = await get(userChatsRef);
	if (snapshot.exists()) {
		return snapshot.val();
	} else {
		return [];
	}
};

const validateChatIds = (chatIds) => {
	if (!Array.isArray(chatIds) || chatIds.length === 0) {
		throw new Error("Invalid input. Provide an array of chat IDs.");
	}

	chatIds.forEach(validateChatId);
};

const validateChatId = (chatId) => {
	if (isNotNumber(chatId)) {
		throw new Error(
			`Invalid chat ID '${chatId}'. Please provide a valid positive number.`
		);
	}
};

const validateUserId = (userId) => {
	if (isNotNumber(userId)) {
		throw new Error(
			`Invalid user ID '${userId}'. Please provide a valid positive number.`
		);
	}
};

const isNotNumber = (number) => {
	return !number || typeof number !== "number" || number <= 0;
};

module.exports = {
	fbGetLastChatId,
	fbCreateChat,
	fbGetChatData,
	fbGetMultipleChatsData,
	fbAddChatMember,
	fbRemoveChatMember,
	fbUserIsChatMember,
	fbGetChatMembers,
	fbGetUserChats,
	fbSendMessage,
	fbReadMessage,
	fbDeleteMessage,
	fbGetChatMessages,
};
