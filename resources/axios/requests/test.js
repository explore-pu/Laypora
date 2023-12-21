import {get} from "@/axios/index";

export const test = (data) => {
  return get("/test", data);
};

export default {test}
