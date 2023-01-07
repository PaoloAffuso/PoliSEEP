import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, query, equalTo, orderByChild, set, update, remove} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
import {uploadBytes, ref as sRef, getDownloadURL} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js';