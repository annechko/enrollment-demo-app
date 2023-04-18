import React, { Component, Suspense } from 'react'
import { BrowserRouter, Route, Routes } from 'react-router-dom'
import './App.scss'
const LoginContainer = React.lazy(() => import('./LoginContainer'))
const RegisterContainer = React.lazy(() => import('./RegisterContainer'))

const loading = (
	<div className="pt-3 text-center">
		<div className="sk-spinner sk-spinner-pulse"></div>
	</div>
)
const urlLogin = window.abeApp.URL_LOGIN
const urlRegister = window.abeApp.URL_REGISTER

class App extends Component {
	render() {
		return (
			<BrowserRouter>
				<Suspense fallback={loading}>
					<Routes>
						<Route exact path={urlLogin} name="Login Page" element={<LoginContainer/>}/>
						<Route exact path={urlRegister} name="Register Page" element={<RegisterContainer/>}/>
					</Routes>
				</Suspense>
			</BrowserRouter>
		)
	}
}

export default App
