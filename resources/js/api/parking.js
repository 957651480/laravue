import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'parking',
    method: 'get',
    params: query,
  });
}

export function fetchParking(id) {
  return request({
    url: 'parking/detail/' + id,
    method: 'get',
  });
}


export function createParking(data) {
  return request({
    url: 'parking/create',
    method: 'post',
    data,
  });
}

export function updateParking(id,data) {
  return request({
    url: 'parking/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteParking(id) {
  return request({
    url: 'parking/delete/' + id,
    method: 'get',
  });
}

export function exportParking() {
  return request({
    url: 'parking/export',
    method: 'get',
    responseType: "blob"
  });
}
