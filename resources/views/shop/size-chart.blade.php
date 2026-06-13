@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Professional Size Chart</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white">
            <thead class="table-dark">
                <tr>
                    <th>Measurement</th>
                    <th>Small</th>
                    <th>Medium</th>
                    <th>Large</th>
                    <th>XL</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>Chest</td><td>34</td><td>36</td><td>40</td><td>44</td></tr>
                <tr><td>Waist</td><td>30</td><td>32</td><td>36</td><td>40</td></tr>
                <tr><td>Hip</td><td>36</td><td>38</td><td>42</td><td>46</td></tr>
                <tr><td>Shoulder</td><td>13</td><td>14</td><td>15</td><td>16</td></tr>
                <tr><td>Arm Length</td><td>20</td><td>21</td><td>22</td><td>23</td></tr>
                <tr><td>Shirt Length</td><td>38</td><td>40</td><td>42</td><td>44</td></tr>
                <tr><td>Trouser Length</td><td>37</td><td>38</td><td>39</td><td>40</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
