import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'houses',
    method: 'get',
    params: query,
  });
}

export function fetchHouse(id) {
  return request({
    url: 'houses/detail/' + id,
    method: 'get',
  });
}


export function createHouse(data) {
  return request({
    url: 'houses/create',
    method: 'post',
    data,
  });
}

export function updateHouse(id,data) {
  return request({
    url: 'houses/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteHouse(id) {
  return request({
    url: 'houses/delete/' + id,
    method: 'get',
  });
}

export function exportHouse() {
  return request({
    url: 'houses/export',
    method: 'get',
    responseType: "blob"
  });
}
