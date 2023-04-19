import 'react-app-polyfill/stable'
// import 'core-js'
import React from 'react'
import {createRoot} from 'react-dom/client'
import App from './App'

createRoot(document.getElementById('root')).render(
	<App/>
)

