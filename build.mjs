import { build } from 'vite';
import { fileURLToPath } from 'url';
import path from 'path';
import { dirname } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

async function buildApp() {
    try {
        await build({
            root: __dirname,
            configFile: path.resolve(__dirname, 'vite.config.js')
        });
        console.log('Build completed successfully!');
    } catch (err) {
        console.error('Build failed:', err);
        process.exit(1);
    }
}

buildApp();
