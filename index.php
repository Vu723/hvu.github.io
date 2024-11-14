<?php
    require_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Trang Web Basic</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <script src="https://cdn.jsdelivr.net/npm/ethers/dist/ethers.min.js"></script>
</head>
<body>
    <h1>Trang web tích hợp MetaMask</h1>
    <button onclick="connectMetaMask()">Kết nối MetaMask</button>
    <p id="walletAddress"></p>
    <p id="walletBalance"></p>

    <?php
        if (isset($_GET['page_layout'])) {
            switch ($_GET['page_layout']) {
                case 'danhsach':
                    require_once 'products/danhsach.php';
                    break;
                case 'them':
                    require_once 'products/them.php';
                    break;
                case 'sua':
                    require_once 'products/sua.php';
                    break;
                case 'xoa':
                    require_once 'products/xoa.php';
                    break;
                
                default:
                    require_once 'products/danhsach.php';
                    break;
            }
        } else {
            require_once 'products/danhsach.php';
        }
    ?>

    <script>
        async function connectMetaMask() {
            if (window.ethereum) {
                try {
                    await window.ethereum.request({ method: 'eth_requestAccounts' });
                    const accounts = await ethereum.request({ method: 'eth_accounts' });
                    document.getElementById('walletAddress').innerText = "Địa chỉ ví: " + accounts[0];
                    getBalance(accounts[0]);
                } catch (error) {
                    console.error("Người dùng đã từ chối kết nối", error);
                }
            } else {
                alert("Bạn cần cài đặt MetaMask để sử dụng chức năng này.");
            }
        }
        async function getBalance(account) {
            const provider = new ethers.providers.Web3Provider(window.ethereum);
            const balance = await provider.getBalance(account);
            document.getElementById('walletBalance').innerText = "Số dư: " + ethers.utils.formatEther(balance) + " ETH";
        }

        window.ethereum.on('accountsChanged', (accounts) => {
            document.getElementById('walletAddress').innerText = "Địa chỉ ví: " + accounts[0];
            getBalance(accounts[0]);
        });

        window.ethereum.on('chainChanged', () => {
            window.location.reload();
        });
    </script>
</body>
</html>
