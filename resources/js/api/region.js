import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'regions',
    method: 'get',
    params: query,
  });
}

export function fetchRegion(id) {
  return request({
    url: 'regions/detail/' + id,
    method: 'get',
  });
}


export function createRegion(data) {
  return request({
    url: 'regions/create',
    method: 'post',
    data,
  });
}

export function updateRegion(id,data) {
  return request({
    url: 'regions/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteRegion(id) {
  return request({
    url: 'regions/delete/' + id,
    method: 'get',
  });
}
