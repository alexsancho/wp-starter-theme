const config = {
    replaceVersion: {
        wordpress: {
            files: "./style.css",
            from: /Version: (.*)/gi,
            to: "Version: "
        },
        php: {
            files: "!(node_modules|dist|vendor)/**/*.php",
            from: /%%NEXT_VERSION%%/g
        }
    }
};
const pjson = require("../../../package.json");
const replace = require("replace-in-file");
const PluginError = require("plugin-error");
const timestamp = require("time-stamp");
const colors = require("ansi-colors");

function getTimestamp() {
    return "[" + colors.white(timestamp("HH:mm:ss")) + "]";
}

function log() {
    const time = getTimestamp();
    process.stdout.write(time + " ");
    console.log.apply(console, arguments);
}

function replaceVersion() {
    return Promise.resolve()
        .then(() => {
            // read current version from package.json
            config.replaceVersion.php.to = pjson.version;
            log(`Replacing ${config.replaceVersion.php.from} with ${config.replaceVersion.php.to} in all PHP files.`);
            const changedFilesPhp = replace.sync(config.replaceVersion.php);
            for (const file of changedFilesPhp) {
                log(`Updated ${file}`);
            }

            // replace WordPress theme version in style.css
            log("Updating WordPress plugin version.");
            config.replaceVersion.wordpress.to += pjson.version;
            const changedFilesWp = replace.sync(config.replaceVersion.wordpress);

            if (changedFilesWp.length > 0) {
                for (const file of changedFilesWp) {
                    log(`Updated ${file}`);
                }
            } else {
                log(colors.yellow("No changes made! Was the version already changed?"));
            }
        })
        .catch(error => {
            throw new PluginError("replaceVersion", error);
        });
}

replaceVersion();

module.exports = replaceVersion;
