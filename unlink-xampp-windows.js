const { execSync } = require("child_process");
const path = require("path");

const targetDir = path
  .join("C:", "xampp", "htdocs", "fitsystem")
  .replace(/\\/g, "\\\\");

const command = `rmdir "${targetDir}"`;

try {
  execSync(command, { stdio: "inherit" });
  console.log("Symbolic link removed successfully.");
} catch (error) {
  console.error("Error removing symbolic link:", error.message);
}
