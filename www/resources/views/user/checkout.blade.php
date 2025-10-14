<p>決済ページへリダイレクトします。</p>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const publicKey = '{{ $publicKey }}';
    const stripe = Stripe(publicKey);

    window.onload = function() {
        stripe.redirectToCheckout({
            sessionId: '{{ $session->id }}'
        }).then(function (result) {
            if (result.error) {
                // ✅ エラー内容をコンソールに出力
                console.error('Stripe redirectToCheckout error:', result.error.message);
            } else {
                // ✅ 正常終了（ただし通常はここには来ない）
                window.location.href = '{{ route('user.cart.cancel') }}';
            }
        }).catch(function (error) {
            // ✅ Promise 自体が reject された場合（通信エラーなど）
            console.error('Unexpected error:', error);
        });
    };
</script>
