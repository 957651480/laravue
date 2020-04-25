import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'banners',
    method: 'get',
    params: query,
  });
}

export function fetchBanner(id) {
  return request({
    url: 'banners/detail/' + id,
    method: 'get',
  });
}


export function createBanner(data) {
  return request({
    url: 'banners/create',
    method: 'post',
    data,
  });
}

export function updateBanner(id,data) {
  return request({
    url: 'banners/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteBanner(id) {
  return request({
    url: 'banners/delete/' + id,
    method: 'get',
  });
}
