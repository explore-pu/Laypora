import {get, post} from "@/axios/index";

export const apiResources = () => {
  return get("/resources");
};

export const apiStoreResource = (data) => {
  return post("/store/resource", data);
};

export default {
  apiResources,
  apiStoreResource,
}
