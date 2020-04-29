import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'house',
    method: 'get',
    params: query,
  });
}

export function fetchHouse(id) {
  return request({
    url: 'house/detail/' + id,
    method: 'get',
  });
}


export function createHouse(data) {
  return request({
    url: 'house/create',
    method: 'post',
    data,
  });
}

export function updateHouse(id,data) {
  return request({
    url: 'house/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteHouse(id) {
  return request({
    url: 'house/delete/' + id,
    method: 'get',
  });
}

export function exportHouse() {
  return request({
    url: 'house/export',
    method: 'get',
    responseType: "blob"
  });
}
