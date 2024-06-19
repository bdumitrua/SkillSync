import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
	broadcaster: "reverb",
	key: import.meta.env.VITE_REVERB_APP_KEY,
	wsHost: import.meta.env.VITE_REVERB_HOST,
	wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
	wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
	forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
	enabledTransports: ["ws", "wss"],
});

// Добавление событий для отладки
window.Echo.connector.pusher.connection.bind("connected", () => {
	console.log("Successfully connected to Pusher!");
});

window.Echo.connector.pusher.connection.bind("error", (err) => {
	console.error("Error connecting to Pusher:", err);
});

const appHtml = document.getElementById("app");

const userId = 2;
window.Echo.channel(`user.notifications.${userId}`).listen(
	"NewNotificationEvent",
	(notification) => {
		console.log(notification);
	}
);

const chatId = 5;
window.Echo.channel(`chat.${chatId}`).listen("MessageSentEvent", (message) => {
	console.log(message);
	appendMessage(appHtml, message.message);
});

// document.addEventListener("DOMContentLoaded", () => {
// 	fetch(`http://localhost:8000/api/chats/${chatId}/messages`, {
// 		headers: {
// 			Authorization:
// 				// Stay calm, dev token
// 				"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2F1dGgvcmVnaXN0ZXIiLCJpYXQiOjE3MTg3Mjg4NzUsImV4cCI6MTcxOTkzODQ3NSwibmJmIjoxNzE4NzI4ODc1LCJqdGkiOiIxSDRPOWVOa2wwRWJiUFd2Iiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjciLCJ1c2VySWQiOjF9.99HSSmHt_GJH3scGVc_kIUyYVYIkDhoHiClEkF3lFHU",
// 		},
// 	})
// 		.catch((error) => {
// 			console.error(error);
// 		})
// 		.then((response) => response.json())
// 		.then((messages) =>
// 			Object.keys(messages).map((key) => ({
// 				id: key,
// 				...messages[key],
// 			}))
// 		)
// 		.then((messages) => fillMessagesContainer(appHtml, messages));
// });

function fillMessagesContainer(container, messages) {
	console.log(messages);

	messages.forEach((message, index) => {
		appendMessage(container, message);
	});
}

function appendMessage(container, message) {
	const messageDiv = document.createElement("div");

	messageDiv.style = "margin-top: 20px;";
	messageDiv.innerHTML = `
			<p>${message.senderId}</p>
			<p>${message.text}</p>
		`;

	container.appendChild(messageDiv);
}
