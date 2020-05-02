import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'information',
    method: 'get',
    params: query,
  });
}

export function fetchInformation(id) {
  return request({
    url: 'information/detail/' + id,
    method: 'get',
  });
}


export function createInformation(data) {
  return request({
    url: 'information/create',
    method: 'post',
    data,
  });
}

export function updateInformation(id,data) {
  return request({
    url: 'information/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteInformation(id) {
  return request({
    url: 'information/delete/' + id,
    method: 'get',
  });
}

export function exportInformation() {
  return request({
    url: 'house/export',
    method: 'get',
    responseType: "blob"
  });
}
