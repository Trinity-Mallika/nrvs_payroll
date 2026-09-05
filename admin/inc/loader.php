<style>
#pageLoaderrr {
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.35);
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(3px);
    z-index: 99999;

    display: flex;
    align-items: center;
    justify-content: center;
}

.loader-centerrr {
    text-align: center;
    padding: 18px 26px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.65);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08); 
    animation: popIn 0.25s ease-out;
} 

.ringggg {
    width: 44px;
    height: 44px;
    border: 4px solid rgba(0, 0, 0, 0.12);
    border-top: 4px solid #0d6efd;
    border-radius: 50%;
    animation: spin 0.9s linear infinite;
    margin: 0 auto 12px;
} 

.branddd {
    margin-top: 6px;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: 2.5px;
        color: #eb3e05;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

@keyframes popIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style> 
<div id="pageLoaderrr">
    <div class="loader-centerrr">
        <div class="ringggg"></div>
        <div class="branddd">N.R.V.S</div>
    </div>
</div> 
<script>
window.addEventListener('load', function () {

    var loader = document.getElementById('pageLoaderrr');

    if (loader) {
        loader.style.display = 'none';
    }

});
</script>