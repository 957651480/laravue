import { deepClone } from '../utils/index.js'
import { asyncRoutes, constantRoutes } from '../router'

const routes = deepClone([...constantRoutes, ...asyncRoutes]);
