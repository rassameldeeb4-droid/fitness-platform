const { Client, LocalAuth } = require('whatsapp-web.js');
const express = require('express');
const qrcode = require('qrcode-terminal');

const app = express();
app.use(express.json());

const PORT = process.env.PORT || 3001;
const API_KEY = process.env.API_KEY || '';

const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: { headless: true, args: ['--no-sandbox'] }
});

client.on('qr', qr => {
    console.log('\n=== Scan this QR with WhatsApp ===');
    qrcode.generate(qr, { small: true });
    console.log('=================================\n');
});

client.on('ready', () => {
    console.log('✓ WhatsApp connected!');
    console.log(`Server running on http://localhost:${PORT}`);
});

client.on('disconnected', reason => {
    console.log('✗ WhatsApp disconnected:', reason);
});

client.initialize();

app.post('/send', (req, res) => {
    if (API_KEY && req.body.api_key !== API_KEY) {
        return res.status(401).json({ error: 'Invalid API key' });
    }
    const { to, message } = req.body;
    if (!to || !message) {
        return res.status(400).json({ error: 'Missing "to" or "message"' });
    }
    const chatId = to.includes('@c.us') ? to : `${to}@c.us`;
    client.sendMessage(chatId, message)
        .then(() => res.json({ success: true }))
        .catch(err => res.status(500).json({ error: err.message }));
});

app.get('/status', (req, res) => {
    res.json({
        connected: client.info ? true : false,
        number: client.info ? client.info.wid.user : null,
    });
});

app.listen(PORT, () => {
    console.log(`Server starting on http://localhost:${PORT}`);
});
