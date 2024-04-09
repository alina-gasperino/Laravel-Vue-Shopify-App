let api_url = 'https://analytics.simplepost.co/api/logVisit';
// let api_url = 'https://app.simplepost.co/api/logVisit';
let apiHeaders = {
    "Content-Type": "application/json"
}

var currentPage = location.href;

function getCurrentPage(ip) {
    generateSessionID(ip, window.location.pathname);
}

function generateSessionID(ip, path) {
    let session = {
        timestamp: new Date().getTime(),
        ip: ip || '',
        path,
    };
    let sessionID = window.btoa(JSON.stringify(session));
    generateVariant(ip, path, sessionID);
}

function listenForVariant(ip, path, sessionID) {
    let urlSearchParams = new URLSearchParams(window.location.search);
    let params = Object.fromEntries(urlSearchParams.entries());
    if (params?.variant) {
        saveCustomerLog(ip, path, sessionID, params.variant);
    }
    else {
        saveCustomerLog(ip, path, sessionID, '');
    }
}

function generateVariant(ip, path, sessionID) {
    listenForVariant(ip, path, sessionID);
}

function getIP() {
    fetch("https://api.ipify.org/?format=json")
        .then((response) => response.json())
        .then((response) => {
            if (response.ip) {
                getCurrentPage(response.ip);
            }
            else {
                getCurrentPage('');
            }
        })
        .catch((error) => {
            getCurrentPage('');
            console.log("Something went wrong while getting the IP Addres");
        });
}

async function saveCustomerLog(ip, path, sessionID, variant) {
    let ipAddress = await fetch("https://api.ipify.org/?format=json");

    let cookiename = 'log_visit__' + new Date().toDateString();

    if(getCookie(cookiename) == ""){
        await fetch(`${api_url}`, {
            method: 'POST',
            body: JSON.stringify({
                shopName: window.Shopify.shop,
                path: path || window.location.pathname,
                variantId: variant || '',
                sessionId: sessionID || generateSessionID(ipAddress?.json()?.ip, window.location.pathname),
                timestamp: new Date(),
                ip: ip || ipAddress?.json()?.ip,
                type: 'activity'
            }),
            headers: { ...apiHeaders }
        });
    
        setCookie(cookiename, true, 1);
    }    
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
}

async function saveCartLog() {
    let cookiename = 'log_cart__' + new Date().toDateString();
    if(getCookie(cookiename) == ""){
        fetch("https://api.ipify.org/?format=json")
        .then((ipAddress) => ipAddress.json())
        .then((ipRes) => {
            let ip = ipRes?.ip || '';
            let variant = window.meta.product.id;
            let urlSearchParams = new URLSearchParams(window.location.search);
            let params = Object.fromEntries(urlSearchParams.entries());
                if (params?.variant) {
                    variant = params?.variant;
            }
            let session = {
                timestamp: new Date().getTime(),
                ip,
                path: window.location.pathname,
            };
            let sessionID = window.btoa(JSON.stringify(session));
            
            fetch(`${api_url}`, {
                method: 'POST',
                body: JSON.stringify({
                    shopName: window.Shopify.shop,
                    path: window.location.pathname,
                    variantId: variant || '',
                    sessionId: sessionID,
                    timestamp: new Date(),
                    ip,
                    type: 'add to cart'
                }),
                headers: { ...apiHeaders }
            }).then(()=>{
                setCookie(cookiename, true, 1)
            });
        })
    }

}

getIP();

setInterval(function () {
    if (currentPage != location.href) {
        currentPage = location.href;
        listenForVariant();
    }
}, 500);


const constantMock = window.fetch;
window.fetch = function(){
    return new Promise((resolve, reject) => {
        constantMock.apply(this, arguments)
        .then((response)=>{
            if(response?.url?.indexOf("/cart/add") > -1){
                saveCartLog();
            }
            resolve(response);
        })
        .catch((error) => {
            reject(error)
        })
    });
}