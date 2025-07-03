#!/bin/bash

echo "=== DNS Propagation Monitor ==="
echo "Target: acraltech.site should resolve to 46.202.161.86"
echo "Current time: $(date)"
echo ""

for i in {1..10}; do
    echo "Check #$i:"
    
    # Get current IP
    CURRENT_IP=$(ping -c 1 acraltech.site 2>/dev/null | grep "PING" | sed -n 's/.*(\([^)]*\)).*/\1/p')
    echo "  Current IP: $CURRENT_IP"
    
    if [ "$CURRENT_IP" = "46.202.161.86" ]; then
        echo "  ✅ DNS propagated! Now testing SSH..."
        
        if timeout 10 ssh -o ConnectTimeout=5 u806021370@acraltech.site "echo 'SSH SUCCESS'" 2>/dev/null; then
            echo "  ✅ SSH connection working!"
            echo ""
            echo "🎉 READY TO DEPLOY!"
            echo "Run: ./deploy-assets.sh"
            exit 0
        else
            echo "  ❌ SSH still not working"
        fi
    else
        echo "  ⏳ Still resolving to Cloudflare ($CURRENT_IP)"
    fi
    
    echo "  Waiting 30 seconds..."
    echo ""
    sleep 30
done

echo "❌ DNS propagation taking longer than expected"
echo "Manual check: ping acraltech.site"
echo "Expected: 46.202.161.86"
