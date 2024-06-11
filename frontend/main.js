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

window.Echo.channel("post-likes-channel").listen("PostLikeEvent", (event) => {
	console.log(event);
});
