var express = require("express");
var router = express.Router();
const { v4: uuidv4 } = require("uuid");

router.get("/send/:chatId", function (req, res, next) {
	const chatId = req.params.chatId;

	res.send(`Sending message to chatId: ${chatId}`);
});

router.delete("/delete/:messageUuid", function (req, res, next) {
	const messageUuid = req.params.messageUuid;

	res.send(`Deleting message with uuid: ${messageUuid}`);
});

router.delete("/delete/forever/:messageUuid", function (req, res, next) {
	const messageUuid = req.params.messageUuid;

	res.send(`Deleting forever message with uuid: ${messageUuid}`);
});

module.exports = router;
