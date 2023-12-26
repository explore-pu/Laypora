import {get} from "@/axios/index";

export const getResources = () => {
  return get("/resources");
};

export default {getResources}
