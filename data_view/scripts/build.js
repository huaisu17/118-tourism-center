const fs = require("node:fs");
const path = require("node:path");

const rootDir = path.resolve(__dirname, "..");
const distDir = path.join(rootDir, "dist");
const entries = ["index.html", "cs", "js", "images", "广东省.svg"];

function ensureCleanDir(dirPath) {
  if (fs.existsSync(dirPath)) {
    fs.rmSync(dirPath, { recursive: true, force: true });
  }
  fs.mkdirSync(dirPath, { recursive: true });
}

function copyEntry(name) {
  const source = path.join(rootDir, name);
  const target = path.join(distDir, name);

  if (!fs.existsSync(source)) {
    throw new Error(`Missing required asset: ${name}`);
  }

  fs.cpSync(source, target, { recursive: true });
}

ensureCleanDir(distDir);
entries.forEach(copyEntry);

console.log(`Build complete: ${distDir}`);
