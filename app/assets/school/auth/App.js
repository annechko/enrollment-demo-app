import React, { Component, Suspense } from 'react'
import { BrowserRouter, Route, Routes } from 'react-router-dom'
import './App.scss'

const Login = React.lazy(() => import('./Login'))
const Register = React.lazy(() => import('./Register'))

const loading = (
	<div className="pt-3 text-center">
		<div className="sk-spinner sk-spinner-pulse"></div>
	</div>
)
class App extends Component {
	render() {
		return (
			<BrowserRouter>
				<Suspense fallback={loading}>
					<Routes>
						<Route exact path={URL_LOGIN} name="Login Page" element={<Login/>}/>
						<Route exact path={URL_REGISTER} name="Register Page" element={<Register/>}/>
					</Routes>
				</Suspense>
			</BrowserRouter>
		)
	}
}

export default App
