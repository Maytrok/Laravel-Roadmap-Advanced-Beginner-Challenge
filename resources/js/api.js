import axios from "axios";

const token = localStorage.getItem("sessionToken");
axios.defaults.withCredentials = true;
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

export default axios;