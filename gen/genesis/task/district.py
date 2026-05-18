# BSD 3-Clause License
#
# Copyright (c) 2026, Paulus Gandung Prakosa <gandung@lists.infradead.org>
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are met:
#
# 1. Redistributions of source code must retain the above copyright notice, this
#    list of conditions and the following disclaimer.
#
# 2. Redistributions in binary form must reproduce the above copyright notice,
#    this list of conditions and the following disclaimer in the documentation
#    and/or other materials provided with the distribution.
#
# 3. Neither the name of the copyright holder nor the names of its
#    contributors may be used to endorse or promote products derived from
#    this software without specific prior written permission.
#
# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
# AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
# IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
# DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
# FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
# DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
# SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
# CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
# OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
# OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

import uuid

from genesis.entity.district import District
from genesis.entity.municipal import Municipal
from genesis.task.base import AbstractTask
from sqlmodel import Session, func, select
from tqdm import trange

class CreateBulkDistrictTask(AbstractTask):
    def invoke(self, engine):
        def func(x):
            return '.'.join(x.split('.')[:-1])

        ldeps = self.dependency_map(engine)
        session = Session(engine)

        for i in trange(len(self.chunks)):
            entity = District(
                formalIdentifier=self.chunks[i][0],
                formalName=self.chunks[i][1].upper(),
                municipalRefId=ldeps[func(self.chunks[i][0])]
            )

            session.add(entity)

        session.commit()
        session.close()

    def guid(self):
        return '<create-bulk-district-task:%s>' % str(uuid.uuid4())

    def dependency_map(self, engine):
        def callback(x):
            return '.'.join(x.split('.')[:-1])

        def count_rec(mid, engine):
            count = None

            with Session(engine) as session:
                statm = select(func.count()).where(Municipal.formalIdentifier == mid)
                count = session.exec(statm).one()

            return count

        def get_rec(mid, engine):
            parent = None

            with Session(engine) as session:
                statm = select(Municipal).where(Municipal.formalIdentifier == mid)
                parent = session.exec(statm).first()

            return parent

        wpid = []

        for chunk in self.chunks:
            wpid.append(chunk[0])

        lpid = list(map(callback, wpid))
        lmap = {}

        for pid in lpid:
            if count_rec(pid, engine) != 1:
                continue

            lmap[pid] = get_rec(pid, engine).id

        return lmap
