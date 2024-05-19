const { execSync } = require("child_process");
const fs = require("fs");
const path = require("path");

const currentDir = process.cwd().replace(/\\/g, "\\\\");
const targetBaseDir = "C:\\xampp\\htdocs";
const targetDir = path.join(targetBaseDir, "fitsystem").replace(/\\/g, "\\\\");

if (!fs.existsSync(targetBaseDir)) {
  fs.mkdirSync(targetBaseDir, { recursive: true });
}

const command = `mklink /D "${targetDir}" "${currentDir}\\src"`;

try {
  execSync(command, { stdio: "inherit" });
  console.log("Symbolic link created successfully.");
} catch (error) {
  console.error("Error creating symbolic link:", error.message);
}
