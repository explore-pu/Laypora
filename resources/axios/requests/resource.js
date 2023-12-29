import {get, post, put, destroy} from "@/axios/index";

export const apiResources = () => {
  return get("/resources");
};

export const apiStoreResource = (data) => {
  return post("/resource", data);
};

export const apiRenameResource = (id, data) => {
  return put(`/resource/${id}`, data);
};

export const apiDestroyResource = (id) => {
  return destroy(`/resource/${id}`);
};

export default {
  apiResources,
  apiStoreResource,
  apiRenameResource,
  apiDestroyResource,
}
