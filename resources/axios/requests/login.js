import {post} from "@/axios/index";

export const login = (data) => {
    return post("/login", data);
};

export default {login}
