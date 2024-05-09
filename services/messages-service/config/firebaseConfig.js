const { initializeApp } = require("firebase/app");
const { getDatabase, ref, set } = require("firebase/database");

const fs = require("fs");
const firebaseConfig = JSON.parse(fs.readFileSync("./firebase.json", "utf8"));

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

module.exports = db;
