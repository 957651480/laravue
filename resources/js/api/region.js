import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'region',
    method: 'get',
    params: query,
  });
}

export function fetchRegion(id) {
  return request({
    url: 'region/detail/' + id,
    method: 'get',
  });
}


export function createRegion(data) {
  return request({
    url: 'region/create',
    method: 'post',
    data,
  });
}

export function updateRegion(id,data) {
  return request({
    url: 'region/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteRegion(id) {
  return request({
    url: 'region/delete/' + id,
    method: 'get',
  });
}
