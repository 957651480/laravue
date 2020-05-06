import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: 'parking/area',
    method: 'get',
    params: query,
  });
}

export function fetchParkingArea(id) {
  return request({
    url: 'parking/area/detail/' + id,
    method: 'get',
  });
}


export function createParkingArea(data) {
  return request({
    url: 'parking/area/create',
    method: 'post',
    data,
  });
}

export function updateParkingArea(id,data) {
  return request({
    url: 'parking/area/update/'+id,
    method: 'post',
    data,
  });
}

export function deleteParkingArea(id) {
  return request({
    url: 'parking/area/delete/' + id,
    method: 'get',
  });
}

