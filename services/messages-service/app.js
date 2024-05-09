const express = require("express");
const cookieParser = require("cookie-parser");
const logger = require("morgan");
require("dotenv").config();

const chatsRouter = require("./routes/chats");
const messagesRouter = require("./routes/messages");

const app = express();

app.use(logger("dev"));
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(cookieParser());

app.use("/messages/chats", chatsRouter);
app.use("/messages", messagesRouter);

app.use((err, req, res, next) => {
	console.error(err.stack);

	const statusCode = err.statusCode || 500;
	const message = err.message || "Internal Server Error";

	res.status(statusCode).json({ error: message });
});

module.exports = app;
