import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'banner',
    method: 'get',
    params: query,
  });
}

export function fetchBanner(id) {
  return request({
    url: 'banner/detail/' + id,
    method: 'get',
  });
}


export function createBanner(data) {
  return request({
    url: 'banner/create',
    method: 'post',
    data,
  });
}

export function updateBanner(id,data) {
  return request({
    url: 'banner/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteBanner(id) {
  return request({
    url: 'banner/delete/' + id,
    method: 'get',
  });
}
