import axios from 'axios';

axios.defaults.baseURL = '/api/';
axios.defaults.withCredentials = true;

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

