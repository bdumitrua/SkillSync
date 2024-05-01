const jwt = require("jsonwebtoken");

function authenticateToken(req, res, next) {
	const authHeader = req.headers["authorization"];
	const token = authHeader && authHeader.split(" ")[1];

	if (token == null) {
		return res.sendStatus(401);
	}

	jwt.verify(token, process.env.ACCESS_TOKEN_SECRET, (err, user) => {
		if (err) {
			if (err.name === "TokenExpiredError") {
				return res.status(401).send({ error: "Token expired" });
			} else if (err.name === "JsonWebTokenError") {
				return res.status(403).send({ error: "Invalid token" });
			} else {
				return res.status(500).send({ error: "Internal server error" });
			}
		}
		req.userId = user.userId;
		req.userEmail = user.email;

		next();
	});
}

module.exports = authenticateToken;
