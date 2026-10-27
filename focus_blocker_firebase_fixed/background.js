// background.js

try {
    importScripts('./firebase-sdk/firebase-app.js');
    importScripts('./firebase-sdk/firebase-database.js');

    const firebaseConfig = {
        apiKey: "AIzaSyAO8Fr1OHyfbBNiK3cuC33qdNDgEymbe1I", // IMPORTANT: Keep your actual API key secret
        authDomain: "focuspro-fyp.firebaseapp.com",
        databaseURL: "https://focuspro-fyp-default-rtdb.asia-southeast1.firebasedatabase.app",
        projectId: "focuspro-fyp",
        storageBucket: "focuspro-fyp.firebasestorage.app", // Corrected: "focuspro-fyp.appspot.com" or "focuspro-fyp.firebasestorage.app" - check your Firebase console
        messagingSenderId: "935476789668",
        appId: "1:935476789668:web:350e24ef51aff668b68131"
    };

    firebase.initializeApp(firebaseConfig);
    const db = firebase.database();

    const userId = "1"; // Replace with your actual Laravel user ID

    let blockedSites = [];
    let isFocusing = false;

    // Ensure DB path is correct and user has permissions
    const userFocusStatusRef = db.ref('focusStatus/' + userId);

    userFocusStatusRef.on('value', snapshot => {
        const data = snapshot.val();
        if (data) {
            isFocusing = data.isFocusing || false;
            // Ensure blockedSites is always an array, even if null/undefined in Firebase
            blockedSites = Array.isArray(data.blockedSites) ? data.blockedSites : [];
            console.log("Updated from Firebase:", isFocusing, "Blocked sites:", blockedSites);
        } else {
            // Handle case where no data exists for the user yet
            isFocusing = false;
            blockedSites = [];
            console.log("No data found in Firebase for user. Focusing set to false, no blocked sites.");
        }
    }, errorObject => {
        console.error("Firebase read failed: " + errorObject.name + " : " + errorObject.message);
        // Optionally, implement retry logic or notify the user
    });

    chrome.tabs.onUpdated.addListener(function(tabId, changeInfo, tab) {
        if (isFocusing && changeInfo.url) {
            // Ensure blockedSites is an array before calling forEach
            if (Array.isArray(blockedSites)) {
                blockedSites.forEach(site => {
                    // Ensure site is a string and not empty before using includes
                    if (typeof site === 'string' && site.trim() !== '' && changeInfo.url.includes(site)) {
                        chrome.tabs.update(tabId, { url: chrome.runtime.getURL("blocked.html") });
                    }
                });
            }
        }
    });

} catch (e) {
    console.error("Error loading or initializing Firebase in service worker:", e);
}