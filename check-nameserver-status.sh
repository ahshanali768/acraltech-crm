#!/bin/bash

echo "=== Nameserver Change Status Monitor ==="
echo "Expected Hostinger IP: 46.202.161.86"
echo "Current time: $(date)"
echo ""

echo "🔍 DNS Resolution Check:"
current_ip=$(ping -c 1 acraltech.site 2>/dev/null | grep "PING" | awk -F'[()]' '{print $2}')
echo "acraltech.site currently resolves to: $current_ip"

if [ "$current_ip" = "46.202.161.86" ]; then
    echo "✅ DNS has switched to Hostinger!"
    echo ""
    echo "🚀 Testing SSH connection:"
    if timeout 10 ssh -o ConnectTimeout=5 u806021370@acraltech.site "echo 'SSH working!' && pwd" 2>/dev/null; then
        echo "✅ SSH is working! Ready to deploy."
        echo ""
        echo "📋 Next steps:"
        echo "1. Run: ./deploy-assets.sh"
        echo "2. Upload updated dashboard"
        echo "3. Test the website"
    else
        echo "❌ SSH not working yet - may need a few more minutes"
    fi
elif [ "$current_ip" = "104.21.13.108" ] || [ "$current_ip" = "172.67.199.215" ]; then
    echo "⏳ Still resolving to Cloudflare - nameserver change not propagated yet"
    echo "   This can take 2-24 hours (usually 2-6 hours)"
else
    echo "❓ Resolving to unexpected IP: $current_ip"
fi

echo ""
echo "🔄 To monitor continuously, run this script again in 30 minutes"
echo "📞 You can also check: https://www.whatsmydns.net/#A/acraltech.site"
