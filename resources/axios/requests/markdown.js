import {get, post} from "@/axios/index";

export const apiShowMarkdown = (id) => {
  return get(`/markdown/${id}`);
};

export const apiSaveMarkdown = (id, data) => {
  return post(`/markdown/${id}`, data);
};

export default {
  apiShowMarkdown,
  apiSaveMarkdown,
}
