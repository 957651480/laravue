import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'auction',
    method: 'get',
    params: query,
  });
}

export function fetchAuction(id) {
  return request({
    url: 'auction/detail/' + id,
    method: 'get',
  });
}


export function createAuction(data) {
  return request({
    url: 'auction/create',
    method: 'post',
    data,
  });
}

export function updateAuction(id,data) {
  return request({
    url: 'auction/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteAuction(id) {
  return request({
    url: 'auction/delete/' + id,
    method: 'get',
  });
}
